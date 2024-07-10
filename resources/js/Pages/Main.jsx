import { Link, Head } from '@inertiajs/react';
import { createRef, useEffect, useState } from 'react';
import Chart from 'chart.js/auto'
import Guest from '@/Layouts/GuestLayout';
import { Container, Grid, Skeleton, Typography } from '@mui/material';
import CalendarComponent from '@/Components/MUIComponents/Mails/CalendarComponent';
import axios from 'axios';

const preparationOfPoints = (obj) => {
    const arr = []
    for(let key in obj) {
        arr.push(obj[key])
    }

    return arr
}

export default function Main({chartPhone, chartMail, entryPoints, generalData, test}) {

    const parse = (chartData) => {
        const data = [];
        for(let key in chartData) {
            data.push({date: key, count: chartData[key]})
        }
        return data
    }

    const [dataMail, setDataMail] = useState(parse(chartMail))
    const [dataPhone, setDataPhone] = useState(parse(chartPhone))
    const [direct, setDirect] = useState(false)

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
        const dateString = e.year() + "-" + (e.month() + 1) + "-" + e.date()
    }
    const toDateChange = (e) => {
        const dateString = e.year() + "-" + (e.month() + 1) + "-" + e.date()
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
                    label: 'Количества писем за пириод',
                    data: dataMail.map(row => row.count)
                    },
                    {
                    label: 'Количества звонков за пириод',
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
            <Grid container sx={{border: 'solid 1px black'}}>
                <Grid item xs={4}>
                    <Container>
                       <Container>
                        <Typography variant='h3'>Письма</Typography>
                        <Typography>
                                {generalData.countMails}
                            </Typography>
                            <Typography>
                                сумма оплаченных счетов: {generalData.sumPriceForMails}
                            </Typography>
                       </Container>
                       <hr />
                        <Container>
                            <Typography variant='h3'>Звонки</Typography>
                            <Typography>
                                {generalData.countCalls}
                            </Typography>
                            <Typography>
                                сумма оплаченных счетов: {generalData.sumPriceForCalls}
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
                    <Container sx={{display: 'flex', justifyContent: 'center'}}>
                        <Container sx={{marginRight: '5px'}}>
                            <CalendarComponent lable={'Начало периода'} dateChange={fromDateChange}/>
                        </Container>
                        <Container sx={{marginRight: '5px'}}>
                            <CalendarComponent lable={'Конец периода'} dateChange={toDateChange}/>
                        </Container>
                    </Container>
                </Grid>
            </Grid>
        </Guest>
    );
}
