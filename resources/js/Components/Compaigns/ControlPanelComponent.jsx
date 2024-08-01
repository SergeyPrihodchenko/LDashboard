import { Link } from "@inertiajs/react";
import { Button, ButtonGroup, Grid } from "@mui/material";
import { useEffect, useState } from "react";

const ControlPanelComponent = ({title}) => {

    const state = {
        wika: false,
        swagelo: false,
        hylok: false,
        hy_lok: false,
    }

    const [checkDisabled, setCheckDisabled] = useState(state);
    useEffect(() => {
        switch (title) {
            case 'wika':
                setCheckDisabled({...checkDisabled, wika: true})
                break;
            case 'swagelo':
                setCheckDisabled({...checkDisabled, swagelo: true})
                break;
            case 'hylok':
                setCheckDisabled({...checkDisabled, hylok: true})
                break;
            case 'hy-lok':
                setCheckDisabled({...checkDisabled, hy_lok: true})
                break;
        
            default:
                break;
        }
    }, [title])

    return (
        <Grid container sx={{padding: '10px'}}>
            <Grid item xs={12}>
                <ButtonGroup color="info" variant="contained" size="large" aria-label="Large button group">
                    <Link href={route('compaigns.wika')}>
                        <Button disabled={checkDisabled.wika}>Wika</Button>
                    </Link>
                    <Link href={route('compaigns.swagelo')}>
                        <Button disabled={checkDisabled.swagelo}>Swagelo</Button>
                    </Link>
                    <Link href={route('compaigns.hylok')}>
                        <Button disabled={checkDisabled.hylok}>Hylok</Button>
                    </Link>
                    <Link href={route('compaigns.hy-lok')}>
                        <Button disabled={checkDisabled.hy_lok}>Hy-lok</Button>
                    </Link>
                </ButtonGroup>
            </Grid>
        </Grid>
    )
}

export default ControlPanelComponent;