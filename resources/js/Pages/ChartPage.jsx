import { createRef, useEffect, useState } from 'react';
import { RestartAlt } from '@mui/icons-material';
import Chart from 'chart.js/auto'
import Guest from '@/Layouts/GuestLayout';
import CalendarComponent from '@/Components/MUIComponents/Mails/CalendarComponent';
import ControlPanelComponent from '@/Components/Chart/ControlPanelComponent';
import axios from 'axios';
import { Alert, Box, Button, Container, Grid, Skeleton, Typography } from '@mui/material';

const preparationOfPoints = (obj) => {
    const arr = []
    for(let key in obj) {
        arr.push(obj[key])
    }

    return arr
}

export default function ChartPage({chartPhone, chartMail, entryPoints, generalData, title}) {

    const reset = () => {
        chart.data.labels = preparationOfPoints(entryPoints).map(point => point)
        chart.data.datasets = [
            {
            label: 'Количества писем за период',
            data: parse(chartMail).map(row => row.count)
            },
            {
            label: 'Количества звонков за период',
            data: parse(chartPhone).map(row => row.count)
            },
        ]
        chart.update()
        setInvoiceData(generalData)
    }

    const parse = (chartData) => {
        const data = [];
        for(let key in chartData) {
            data.push({date: key, count: chartData[key]})
        }
        return data
    }

    const switchData = () => {
        if(dateFrom.length == 0 || dateTo.length == 0) {
            setDateError(true)
            return
        }

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
            setInvoiceData({...invoiveData, 
                countMails: res.data.countMails,
                sumPriceForMails: res.data.sumPriceForMails,
                countCalls: res.data.countCalls,
                sumPriceForCalls: res.data.sumPriceForCalls
            })

            chart.data.labels = preparationOfPoints(res.data.entryPoints).map(point => point)
            chart.data.datasets = [
                {
                label: 'Количества писем за период',
                data: parse(res.data.chartInvoice).map(row => row.count)
                },
                {
                label: 'Количества звонков за период',
                data: parse(res.data.chartPhone).map(row => row.count)
                },
            ]
            chart.update()
        })
        .catch(err => console.log(err))
    }

    const [dataMail, setDataMail] = useState(parse(chartMail))
    const [dataPhone, setDataPhone] = useState(parse(chartPhone))
    const [dataEntryPoints, setDataEntryPoints] = useState(entryPoints)
    const [direct, setDirect] = useState(false)
    const [dateFrom, setDateFrom] = useState('')
    const [dateTo, setDateTo] = useState('')
    const [invoiveData, setInvoiceData] = useState(generalData)
    const [dateError, setDateError] = useState(false)
    const [chart, setChart] = useState('')
    const [titleSite, setTitleSite] = useState(title)

    const fetchDirect = () => {

        let routePath = ''

        switch (titleSite) {            
            case 'wika':
                routePath = 'chart.wika.direct'
                break;

            case 'swagelo':
                routePath = 'chart.swagelo.direct'
                break;
        
            default:
                break;
        }
        axios.post(route(routePath))
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
        const newChart = new Chart(
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
       )

       setChart(newChart)

    });
            

    useEffect(() => {
        load(dataEntryPoints, dataMail, dataPhone)
        fetchDirect()
    }, [])

    return (
        <Guest>
            <ControlPanelComponent/>
            <hr />
            <Grid item xs={8}>
                <Container sx={{display: 'flex', justifyContent: 'start', alignItems: 'center', gap: '10px'}}>
                        <CalendarComponent lable={'Начало периода'} dateChange={fromDateChange}/>
                        <CalendarComponent lable={'Конец периода'} dateChange={toDateChange}/>
                        <Box sx={{display: 'flex', flexDirection: 'column', flexGrow: '8px', rowGap: .5, padding: '4px' }}>
                            <Button variant='contained' color='primary' onClick={switchData}>Просмотреть</Button>
                            <Button variant='contained' color='primary' onClick={reset}>Обновить<RestartAlt/></Button>
                        </Box>
                        {dateError ? <Alert severity='error'>Не корректный диапазон даты</Alert> : ''}
                </Container>
                <hr style={{marginTop: '15px'}}/>
            </Grid>
            <div><canvas style={{width: '1400px', height: '500px', margin: '0 auto'}} ref={chartRef} id="acquisitions"></canvas></div>
            <Grid container sx={{borderTop: 'solid 1px', marginTop: 1.5, padding: 1}}>
                <Grid item xs={12}>
                    <Box className='present_data_box'>
                       <Box>
                        <Typography variant='h3'>Письма</Typography>
                        <Typography>
                                <span className='titile_header'>общее количество писем: </span>{invoiveData.countMails}
                            </Typography>
                            <Typography>
                                <span className='titile_header'>сумма оплаченных счетов: </span>{invoiveData.sumPriceForMails}
                            </Typography>
                       </Box>
                        <Box>
                            <Typography variant='h3'>Звонки</Typography>
                            <Typography>
                                <span className='titile_header'>общее количество звонков: </span>{invoiveData.countCalls}
                            </Typography>
                            <Typography>
                                <span className='titile_header'>сумма оплаченных счетов: </span>{invoiveData.sumPriceForCalls}
                            </Typography>
                        </Box>
                        {!direct ? <Box><Skeleton width={600} height={250}/></Box> : 
                        <Box>
                            <Typography variant='h3'>Директ</Typography>
                            <Typography>
                               с {direct.fromDate} по {direct.toDate} 
                            </Typography>
                            <Typography>
                                <span className='titile_header'>общее количество кликов: </span>{direct.countCliks}
                            </Typography>
                            <Typography>
                                <span className='titile_header'>общая сумма за клики: </span>{direct.sumPrice}
                            </Typography>
                        </Box>
                        }
                    </Box>
                </Grid>
            </Grid>
        </Guest>
    );
}
