import * as React from 'react';
import Button from '@mui/material/Button';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import { Container, Grid, Skeleton, Typography } from '@mui/material';

export default function ModalComponent({open, handleClose, dataModal, skeleton}) {

    const render = React.useCallback((obj) => {
        const html = [];
        for(let key in obj) {

            html.push((
                <Grid container className='card_group'>
                    <Grid item xs={12}>
                        <Typography variant='h4'>
                            {key}
                        </Typography>
                    </Grid>
                    <Grid container className='card_group_items'>
                       
                    </Grid>
                </Grid>
            ))
        }
        return html
    })

  return (
    <React.Fragment>
      <Dialog
        open={open}
        onClose={handleClose}
        aria-labelledby="alert-dialog-title"
        aria-describedby="alert-dialog-description"
        maxWidth='xl'
      >
        <DialogContent>
           <Grid container spacing={2} maxWidth={1200} width={'100%'} height={'760px'}>
                {
                    !skeleton ?
                    <>
                    <Grid  item xs={4}>
                        <Skeleton variant="rectangular" width={360} height={660} />
                    </Grid>
                    <Grid  item xs={6}>
                        <Skeleton variant="rectangular" width={760} height={660} />
                    </Grid>
                    </> : ''
                }
                {dataModal ? render(dataModal.data) : ''}
           </Grid>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleClose}>Закрыть</Button>
        </DialogActions>
      </Dialog>
    </React.Fragment>
  );
}