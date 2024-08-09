import ControlPanelComponent from '@/Components/Compaigns/ControlPanelComponent';
import TableComponent from '@/Components/MUIComponents/Compaigns/TableComponent';
import Guest from '@/Layouts/GuestLayout';
import { CircularProgress, Grid } from '@mui/material';
import axios from 'axios';
import { useEffect, useState } from 'react';

const Compaigns = ({data}) => {
    
    
    const [compaigns, setCompaigns] = useState([]);
    const [routePath, ] = useState(data.routePath);
    const [loader, setLoader] = useState(false);

    const updateDirectDate = (date) => {
        setDateUpdate(date)
    }
    
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
            
            setCompaigns(result.data.direct)       
            setLoader(true)
        })
        .catch(err => {
            console.log(err);
        })
    }

    useEffect(() => {
        fetchInvoice()
    }, [])

    return (
        <Guest dateUpdateDirect={data.dateUpdateDirect} updateDirectDate={updateDirectDate}>
            <ControlPanelComponent title={data.routePath}/>
            <hr />
            <br/>
            <Grid container>
                <TableComponent compaignsData={compaigns}/>
            </Grid>
        </Guest>
    )
}

export default Compaigns