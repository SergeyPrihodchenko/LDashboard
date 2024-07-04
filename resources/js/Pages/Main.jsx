import { Link, Head } from '@inertiajs/react';
import { createRef, useEffect, useState } from 'react';
import Chart from 'chart.js/auto'

export default function Main() {

    

    const chartRef = createRef(null)
    
    const load = (async function() {
                    const data = [
                    { year: 2010, count: 10 },
                    { year: 2011, count: 20 },
                    { year: 2012, count: 15 },
                    { year: 2013, count: 25 },
                    { year: 2014, count: 22 },
                    { year: 2015, count: 30 },
                    { year: 2016, count: 28 },
                    ];
                    const data2 = [
                    { year: 2010, count: 5 },
                    { year: 2011, count: 10 },
                    { year: 2012, count: 7 },
                    { year: 2013, count: 12 },
                    { year: 2014, count: 11 },
                    { year: 2015, count: 15 },
                    { year: 2016, count: 14 },
                    ];
                    const data3 = [
                    { year: 2010, count: 20 },
                    { year: 2011, count: 40 },
                    { year: 2012, count: 30 },
                    { year: 2013, count: 50 },
                    { year: 2014, count: 44 },
                    { year: 2015, count: 60 },
                    { year: 2016, count: 58 },
                    ];
                
                    new Chart(
                        chartRef.current,
                    {
                        type: 'line',
                        data: {
                        labels: data.map(row => row.year),
                        datasets: [
                            {
                            label: 'Acquisitions by year',
                            data: data.map(row => row.count)
                            },
                            {
                                label: 'Acquisitions by year',
                                data: data2.map(row => row.count)
                            },
                            {
                                label: 'Acquisitions by year',
                                data: data3.map(row => row.count)
                            }
                        ]
                        }
                    }
                    );
                });
    

    useEffect(() => {
        load()
    }, [])

    return (
        <>
            <div ><canvas style={{width: '800px', margin: '0 auto'}} onLoad={load} ref={chartRef} id="acquisitions"></canvas></div>
        </>
    );
}
