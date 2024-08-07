import ControlPanelComponent from '@/Components/Compaigns/ControlPanelComponent';
import Guest from '@/Layouts/GuestLayout';
import { Box, CircularProgress, Grid } from '@mui/material';
import axios from 'axios';
import Chart from 'chart.js/auto'
import { createRef, useEffect, useState } from 'react';

const preparation = (compaignData) => {
    const compaigns = {
        points: [],
        values: []
    }

    for(let key in compaignData) {
        compaigns.points.push(compaignData[key].campaignName)
        compaigns.values.push(compaignData[key].cost)
    }

    return compaigns
}

const Compaigns = ({data}) => {

    console.log(data);

    const [compaigns, setCompaigns] = useState(preparation(data.direct));
    const chartRef = createRef(null);
    const [routePath, ] = useState(data.routePath);
    const [loader, setLoader] = useState(false);
    const [dateUpdate, setDateUpdate] = useState(data.dateUpdateDirect)

    const fetchInvoice = () => {

        let routing = 'compaigns.wika.invoice'

        switch (routePath) {
            case 'wika':
                routing = 'compaigns.wika.invoice'
                break;

            case 'swagelo':
                routing = 'compaigns.swagelo.invoice'
                break;
                
            case 'hylok':
                routing = 'compaigns.hylok.invoice'
                break;

            case 'hy-lok':
                routing = 'compaigns.hy-lok.invoice'
                break;
        
            default:
                break;
        }

        axios.post(route(routing))
        .then(result => {
            console.log(result.data);
            setClients(result.data)
            setLoader(true)
        })
        .catch(err => {
            console.log(err);
        })
    }

    const load = (async function(dataCompaign) {

        const newChart = new Chart(
            chartRef.current,
           {
               type: 'doughnut',
               data: {
               labels: dataCompaign.points,
               datasets: [
                   {
                   label: 'Затраты по компании',
                   data: dataCompaign.values
                   }
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
        load(compaigns)
        fetchInvoice()
    }, [])

    return (
        <Guest dateUpdateDirect={dateUpdate} updateDirectDate={updateDirectDate}>
            <ControlPanelComponent title={data.routePath}/>
            <hr />
            <br/>
            <Grid container>
                <Grid item xs={7}>
                 
                </Grid>
                <Grid item xs={5}><canvas style={{width: '600px', height: '300px', margin: '0 auto'}} onLoad={load} ref={chartRef} id="acquisitions"></canvas></Grid>
            </Grid>
        </Guest>
    )
}

export default Compaigns