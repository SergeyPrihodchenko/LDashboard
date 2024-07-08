import { Link, Head } from '@inertiajs/react';
import { createRef, useEffect, useState } from 'react';
import Chart from 'chart.js/auto'
import Guest from '@/Layouts/GuestLayout';
import { Grid } from '@mui/material';

export default function Main({chartData}) {

    console.log(chartData.direct);
    const parse = (chartData) => {
        const data = [];
        for(let key in chartData) {
            data.push({date: key, count: chartData[key]})
        }
        return data
    }

    const [data, setData] = useState(parse(chartData.chart))
    
    console.log(chartData.generalData);
    const chartRef = createRef(null)

    
    const load = (async function(data) {
        new Chart(
             chartRef.current,
            {
                type: 'line',
                data: {
                labels: data.map(row => row.date),
                datasets: [
                    {
                    label: 'Количества писем за пириод',
                    data: data.map(row => row.count)
                    }
                ],
                }
            }
        );
    });
            

    useEffect(() => {
        load(data)
        return () => {
            chartRef.current.destroy();
        };
    }, [])

    return (
        <Guest>
            <div><canvas style={{width: '1400px', height: '500px', margin: '0 auto'}} onLoad={load} ref={chartRef} id="acquisitions"></canvas></div>
            <Grid container sx={{height: '350px', border: 'solid 1px black'}}>
                <Grid item xs={4}>
                    
                </Grid>
                <Grid item xs={8}>

                </Grid>
            </Grid>
        </Guest>
    );
}
