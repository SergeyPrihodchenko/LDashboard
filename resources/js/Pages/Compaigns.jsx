import ControlPanelComponent from '@/Components/Compaigns/ControlPanelComponent';
import AccordionCompaign from '@/Components/MUIComponents/Compaigns/Accordion';
import StickySubheader from '@/Components/MUIComponents/Compaigns/StickySubheader';
import Guest from '@/Layouts/GuestLayout';
import { Box, Grid } from '@mui/material';
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

    const [compaigns, setCompaigns] = useState(preparation(data.direct))
    const [clients, setClients] = useState([])
    const [chart, setChart] = useState('')
    const chartRef = createRef(null)

    const fetchInvoice = () => {

        axios.post(route('compaigns.wika.invoice'))
        .then(result => {
            console.log(result.data);
            setClients(result.data)
        })
        .catch(err => {
            console.log(err);
        })
    }

    const parser = (compaignData) => {
        const domElems = []
        for(let key in compaignData) {

            domElems.push(
                <Box className="box_accordion" key={key}>
                    <AccordionCompaign
                        title={compaignData[key].campaignName}
                        cost={compaignData[key].cost}
                        details={compaignData[key].AdGroupId}
                    />
                </Box>
            )
        }

        return domElems
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

    useEffect(() => {
        load(compaigns)
        fetchInvoice()
    }, [])

    return (
        <Guest>
            <ControlPanelComponent/>
            <hr />
            <br/>
            <Grid container>
                <Grid item xs={7}>
                    {/* {parser(data.direct)} */}
                    <StickySubheader direct={data.direct} clients={clients}/>
                </Grid>
                <Grid item xs={5}><canvas style={{width: '600px', height: '300px', margin: '0 auto'}} onLoad={load} ref={chartRef} id="acquisitions"></canvas></Grid>
            </Grid>
        </Guest>
    )
}

export default Compaigns