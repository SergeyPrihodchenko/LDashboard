import * as React from 'react';
import Button from '@mui/material/Button';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import { Container, Grid, Skeleton, Typography } from '@mui/material';

export default function ModalComponent({open, handleClose, dataModal, skeleton, title}) {

    console.log(dataModal.data);

    const render = React.useCallback((obj) => {
        const html = [];
        for(let key in obj) {

            html.push((
                <Grid container>
                    <Grid item xs={12}>
                        <Typography variant='h2'>
                            {key}
                        </Typography>
                    </Grid>
                    <Grid container>
                        {
                            obj[key].map((elem, i) => {
                                if(elem.title == '1С') {
                                    return (
                                        <Grid container key={i + elem.invoice_date}>
                                            <Grid item xs={12}>
                                            <Container sx={{display: 'flex', flexDirection: 'column'}}>
                                                <Typography variant='h3'>
                                                    {elem.title}
                                                </Typography>
                                                <Typography variant='p'>
                                                    {elem.invoice_status}
                                                </Typography>
                                                <Typography variant='p'>
                                                    {elem.invoice_price}
                                                </Typography>
                                                <Typography variant='p'>
                                                    {elem.invoice_date}
                                                </Typography>
                                            </Container>
                                            </Grid>
                                        </Grid>
                                    )
                                } else {
                                    return ( <Grid container key={i + elem.invoice_date}>
                                            <Grid item xs={12}>
                                            <Container sx={{display: 'flex', flexDirection: 'column'}}>
                                                <Typography variant='h3'>
                                                    {elem.title}
                                                </Typography>
                                                <Typography variant='p'>
                                                    {elem.date}
                                                </Typography>
                                                <Typography variant='p'>
                                                    {elem.url}
                                                </Typography>
                                                <Typography variant='p'>
                                                    {elem.favicon}
                                                </Typography>
                                                <Typography variant='p'>
                                                    посещения: {elem.meric_visits}
                                                </Typography>
                                                <Typography variant='p'>
                                                    пользователи: {elem.meric_users}
                                                </Typography>
                                            </Container>
                                            </Grid>
                                        </Grid>
                                   )
                                }
                            })
                        }
                    </Grid>
                </Grid>
            ))
        }
        console.log(html);
        return html
    })
  return (
    <React.Fragment>
      <Dialog
        open={open}
        onClose={handleClose}
        aria-labelledby="alert-dialog-title"
        aria-describedby="alert-dialog-description"
      >
        <DialogContent>
            {
            !skeleton ?
            <>
            <Skeleton variant="rectangular" width={210} height={60} />
            <Skeleton variant="rounded" width={210} height={60} />
            </> : ''
            }
           <Grid container spacing={2}>
                <Grid item xs={4}>
                    <Typography variant='p' sx={{display: 'block'}}>
                        {title}
                    </Typography>
                    <Typography variant='p' sx={{display: 'block'}}>
                        {title}
                    </Typography>
                    <Typography variant='p' sx={{display: 'block'}}>
                        {title}
                    </Typography>
                    <Typography variant='p' sx={{display: 'block'}}>
                        {title}
                    </Typography>
                    <Typography variant='p' sx={{display: 'block'}}>
                        {title}
                    </Typography>
                </Grid>
                <Grid item xs={8}>
                    {dataModal ? render(dataModal.data) : ''}
                </Grid>
           </Grid>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleClose}>Закрыть</Button>
        </DialogActions>
      </Dialog>
    </React.Fragment>
  );
}