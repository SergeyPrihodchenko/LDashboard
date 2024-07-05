import { Link, Head } from '@inertiajs/react';
import { createRef, useEffect, useState } from 'react';
import Chart from 'chart.js/auto'

export default function Main({chartData}) {

    const parse = (chartData) => {
        const data = [];
        for(let key in chartData) {
            data.push({date: key, count: chartData[key]})
        }
        return data
    }

    const [data, setData] = useState(parse(chartData.chart))
    
    console.log(chartData.emailsCount);
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
                        ]
                        }
                    }
                    );
                });
    

    useEffect(() => {
        load(data)
    }, [])

    return (
        <>
            <div ><canvas style={{width: '800px', margin: '0 auto'}} onLoad={load} ref={chartRef} id="acquisitions"></canvas></div>
        </>
    );
}
