import { Link } from "@inertiajs/react";
import { Button, ButtonGroup, Grid } from "@mui/material";

const ControlPanelComponent = () => {
    return (
        <Grid container sx={{padding: '10px'}}>
            <Grid item xs={12}>
                <ButtonGroup color="info" variant="contained" size="large" aria-label="Large button group">
                    <Link href={route('compaigns.wika')}>
                        <Button>Wika</Button>
                    </Link>
                    <Link href={route('compaigns.swagelo')}>
                        <Button>Swagelo</Button>
                    </Link>
                    <Link href={route('hylok')}>
                        <Button>Hylok</Button>
                    </Link>
                </ButtonGroup>
            </Grid>
        </Grid>
    )
}

export default ControlPanelComponent;