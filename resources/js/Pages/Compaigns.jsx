import ControlPanelComponent from '@/Components/Compaigns/ControlPanelComponent';
import AccordionCompaign from '@/Components/MUIComponents/Compaigns/Accordion';
import Guest from '@/Layouts/GuestLayout';
import { Box, Grid, Typography } from '@mui/material';
import Chart from 'chart.js/auto'
import { createRef, useEffect, useState } from 'react';

const Compaigns = ({data}) => {

    const parser = (compaignData) => {
        const domElems = []

        for(let key in compaignData) {

            domElems.push(
                <Box className="box_accordion">
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

    const [compaigns, setCompaigns] = useState(preparation(data))
    const [chart, setChart] = useState('')
    const chartRef = createRef(null)

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
    })

    return (
        <Guest>
            <ControlPanelComponent/>
            <hr />
            <Grid container>
                <Grid item xs={5}>
                    {parser(data)}
                </Grid>
                <Grid item xs={7}><canvas style={{width: '800px', height: '300px', margin: '0 auto'}} onLoad={load} ref={chartRef} id="acquisitions"></canvas></Grid>
            </Grid>
        </Guest>
    )
}

export default Compaigns