import ApplicationLogo from '@/Components/ApplicationLogo';
import Header from '@/Components/Header';
import { Link } from '@inertiajs/react';
import { Grid } from '@mui/material';

export default function Guest({ children }) {
    return (
        <Grid container sx={{margin: '0 auto'}}>
            <Grid item sx={{ width: '100%', height: '100vh', display: 'flex', flexDirection: 'column', justifyContent: 'space-between'}}>
                <Grid container sx={{width: '100%', background: 'black', padding: 1}}>
                    <Grid item xs={12}>
                        <Header/>
                    </Grid>
                </Grid>
                <Grid container sx={{maxWidth: '1600px', width: '100%', padding: '15px 0', margin: '0 auto', height: '100%'}}>
                    <Grid item xs={12} sx={{margin: '0 auto'}}>
                        {children}
                    </Grid>
                </Grid>
                <Grid container sx={{width: '100%'}} className='footer'>
                    <Grid item xs={12}>
                        <h1>footer</h1>
                    </Grid>
                </Grid>
            </Grid>
        </Grid>
    );
}
