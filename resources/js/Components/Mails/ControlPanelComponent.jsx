import { Link } from "@inertiajs/react";
import { Button, ButtonGroup, Grid } from "@mui/material";

const ControlPanelComponent = () => {
    return (
        <Grid container sx={{padding: '10px'}}>
            <Grid item xs={12}>
                <ButtonGroup variant="contained" size="large" aria-label="Large button group">
                    <Link href={route('wika')}>
                        <Button>Wika</Button>
                    </Link>
                    <Link href={route('swagelo')}>
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