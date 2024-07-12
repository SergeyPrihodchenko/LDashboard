import { Link, Head } from '@inertiajs/react';
import { createRef, useEffect, useState } from 'react';
import Chart from 'chart.js/auto'
import Guest from '@/Layouts/GuestLayout';
import { Alert, Box, Button, Container, Grid, Skeleton, Typography } from '@mui/material';
import CalendarComponent from '@/Components/MUIComponents/Mails/CalendarComponent';
import axios from 'axios';

const preparationOfPoints = (obj) => {
    const arr = []
    for(let key in obj) {
        arr.push(obj[key])
    }

    return arr
}

export default function ChartPage({chartPhone, chartMail, entryPoints, generalData}) {

    const parse = (chartData) => {
        const data = [];
        for(let key in chartData) {
            data.push({date: key, count: chartData[key]})
        }
        return data
    }

    const switchData = () => {

        if((new Date(dateFrom)) > (new Date(dateTo))) {

            setDateError(true)
            return
        }
        setDateError(false)
        const data = new FormData()
        data.set('dateFrom', dateFrom)
        data.set('dateTo', dateTo)

        axios.post(route('chart.whika'), data)
        .then(res => {
            console.log(res.data);
            setInvoiceData({...invoiveData, 
                countMails: res.data.countMails,
                sumPriceForMails: res.data.sumPriceForMails,
                countCalls: res.data.countCalls,
                sumPriceForCalls: res.data.sumPriceForCalls
            })

        })
        .catch(err => console.log(err))
    }

    const [dataMail, setDataMail] = useState(parse(chartMail))
    const [dataPhone, setDataPhone] = useState(parse(chartPhone))
    const [direct, setDirect] = useState(false)
    const [dateFrom, setDateFrom] = useState('')
    const [dateTo, setDateTo] = useState('')
    const [invoiveData, setInvoiceData] = useState(generalData)
    const [dateError, setDateError] = useState(false)

    const fetchDirect = () => {
        axios.post(route('wika.direct'))
        .then(async res => {
            setDirect(res.data)
        })
        .catch(err => {
            console.log(err);
        })
    }

    const fromDateChange = (e) => {
        setDateFrom(e.year() + "-" + (e.month() + 1) + "-" + e.date())
    }
    const toDateChange = (e) => {
        setDateTo(e.year() + "-" + (e.month() + 1) + "-" + e.date())
    }
    
    const chartRef = createRef(null)

    const load = (async function(entryPoints, dataMail, dataPhone) {
        new Chart(
             chartRef.current,
            {
                type: 'line',
                data: {
                labels: preparationOfPoints(entryPoints).map(point => point),
                datasets: [
                    {
                    label: 'Количества писем за период',
                    data: dataMail.map(row => row.count)
                    },
                    {
                    label: 'Количества звонков за период',
                    data: dataPhone.map(row => row.count)
                    },
                ],
                }
            }
        );
    });
            

    useEffect(() => {
        load(entryPoints, dataMail, dataPhone)
        fetchDirect()
    }, [])

    return (
        <Guest>
            <div><canvas style={{width: '1400px', height: '500px', margin: '0 auto'}} onLoad={load} ref={chartRef} id="acquisitions"></canvas></div>
            <Grid container sx={{borderTop: 'solid 1px', marginTop: 1.5, padding: 1}}>
                <Grid item xs={4}>
                    <Container>
                       <Container>
                        <Typography variant='h3'>Письма</Typography>
                        <Typography>
                                {invoiveData.countMails}
                            </Typography>
                            <Typography>
                                сумма оплаченных счетов: {invoiveData.sumPriceForMails}
                            </Typography>
                       </Container>
                       <hr />
                        <Container>
                            <Typography variant='h3'>Звонки</Typography>
                            <Typography>
                                {invoiveData.countCalls}
                            </Typography>
                            <Typography>
                                сумма оплаченных счетов: {invoiveData.sumPriceForCalls}
                            </Typography>
                        </Container>
                        <hr />
                        {!direct ? <Skeleton width={600} height={250}/> : 
                        <Container>
                            <Typography variant='h3'>Директ</Typography>
                            <Typography>
                               с {direct.fromDate} по {direct.toDate} 
                            </Typography>
                            <Typography>
                                {direct.countCliks}
                            </Typography>
                            <Typography>
                                {direct.sumPrice}
                            </Typography>
                        </Container>
                        }
                    </Container>
                </Grid>
                <Grid item xs={8}>
                    <Container sx={{display: 'flex', justifyContent: 'start'}}>
                        <Box sx={{marginRight: '5px'}}>
                            <Box>
                                <CalendarComponent lable={'Начало периода'} dateChange={fromDateChange}/>
                            </Box>
                            <Box>
                                <CalendarComponent lable={'Конец периода'} dateChange={toDateChange}/>
                            </Box>
                        </Box>
                        <Box sx={{padding: '8px'}}>
                            <Button variant='contained' color='primary' onClick={switchData}>Просмотреть</Button>
                            {dateError ? <Alert sx={{marginTop: '15px'}} severity='error'>Не корректный диапазон даты</Alert> : ''}
                        </Box>
                    </Container>
                </Grid>
            </Grid>
        </Guest>
    );
}
