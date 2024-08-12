import { createRef, useEffect, useState } from 'react';
import { RestartAlt } from '@mui/icons-material';
import Chart from 'chart.js/auto'
import Guest from '@/Layouts/GuestLayout';
import CalendarComponent from '@/Components/MUIComponents/Chart/CalendarComponent';
import ControlPanelComponent from '@/Components/Chart/ControlPanelComponent';
import axios from 'axios';
import { Alert, Box, Button, Container, Grid, Skeleton, Typography } from '@mui/material';
import BasicTable from '@/Components/MUIComponents/Chart/TableComponent';

const preparationOfPoints = (obj) => {
    const arr = []
    for(let key in obj) {
        arr.push(obj[key])
    }

    return arr
}

export default function ChartPage({chartPhone, chartMail, entryPoints, generalData, dateUpdateDirect, title}) {

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

        let routePath = switchRoute();

        switch (titleSite) {            
            case 'wika':
                routePath = 'chart.wika.byDate'
                break;

            case 'swagelo':
                routePath = 'chart.swagelo.byDate'
                break;

            case 'hylok':
                routePath = 'chart.hylok.byDate'
                break;

            case 'hy-lok':
                routePath = 'chart.hy-lok.byDate'
                break;
        }

        axios.post(route(routePath), data)
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
    const [dateUpdate, setDateUpdate] = useState(dateUpdateDirect)
    const [castomMetric, setCastopMetric] = useState(false)

    const fetchDirect = () => {

        let routePath = ''

        switch (titleSite) {            
            case 'wika':
                routePath = 'chart.wika.direct'
                break;

            case 'swagelo':
                routePath = 'chart.swagelo.direct'
                break;

            case 'hylok':
                routePath = 'chart.hylok.direct'
                break;

            case 'hy-lok':
                routePath = 'chart.hy-lok.direct'
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

    const fetchCastomMetric = () => {

        let routePath = ''

        switch (titleSite) {            
            case 'wika':
                routePath = 'chart.wika.castom'
                break;

            case 'swagelo':
                routePath = 'chart.swagelo.castom'
                break;

            case 'hylok':
                routePath = 'chart.hylok.castom'
                break;

            case 'hy-lok':
                routePath = 'chart.hy-lok.castom'
                break;
        }

        axios.post(route(routePath))
        .then(async res => {
            console.log(res.data);
            
            setCastopMetric({
                cpl: res.data.cpl,
                cpc: res.data.cpc,
                invoices: res.data.invoices,
                visits: res.data.visits,
                invoicesMail: res.data.invoicesMail,
                invoicePhones: res.data.invoicePhones,
                mailPhones: res.data.mailPrice,
                phonePhones: res.data.phonePrice,
            })
            
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

    const updateDirectDate = (date) => {
        setDateUpdate(date)
    }
            

    useEffect(() => {
        load(dataEntryPoints, dataMail, dataPhone)
        fetchDirect()
        fetchCastomMetric()
    }, [])

    return (
        <Guest dateUpdateDirect={dateUpdate} updateDirectDate={updateDirectDate}>
            <ControlPanelComponent title={title}/>
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
            <Grid container>
                <Grid item xs={2}>
                <Grid container height={'100%'}>
                <Grid item xs={12}>
                    <Box className='present_data_box'>
                       <Box borderRight={'solid 1px'} paddingRight={'5px'} maxWidth={'250px'} width={'100%'}>
                        <Typography variant='h6'>Письма</Typography>
                        <Typography>
                                <span className='titile_header'>общее количество писем: </span>{invoiveData.countMails}
                            </Typography>
                            <Typography>
                                <span className='titile_header'>сумма оплаченных счетов: </span>{invoiveData.sumPriceForMails}
                            </Typography>
                       </Box>
                        <Box borderRight={'solid 1px'} paddingRight={'5px'} maxWidth={'250px'} width={'100%'}>
                            <Typography variant='h6'>Звонки</Typography>
                            <Typography>
                                <span className='titile_header'>общее количество звонков: </span>{invoiveData.countCalls}
                            </Typography>
                            <Typography>
                                <span className='titile_header'>сумма оплаченных счетов: </span>{invoiveData.sumPriceForCalls}
                            </Typography>
                        </Box>
                        {!direct ? <Box><Skeleton width={250} height={135}/></Box> : 
                        <Box borderRight={'solid 1px'} paddingRight={'5px'} maxWidth={'250px'} width={'100%'}>
                            <Typography variant='h6'>Директ</Typography>
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
                </Grid>
                <Grid item xs={10}>
                    <div>
                        <canvas style={{width: '1400px', height: '500px', margin: '0 auto'}} ref={chartRef} id="acquisitions"></canvas>
                    </div>
                </Grid>
            </Grid>
            <hr style={{margin: '10px 0'}}/>
            <Grid container padding={'10px 0'}  margin={'10px'}>
                {!castomMetric ? <Skeleton width={'100%'} height={50}/>:
                    <Grid item xs={12}>
                        <BasicTable castomMetric={castomMetric}/>
                    </Grid>
                }
            </Grid>
        </Guest>
    );
}
