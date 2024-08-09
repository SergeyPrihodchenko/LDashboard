import * as React from 'react';
import Button from '@mui/material/Button';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import { Box, Grid, Skeleton, Typography } from '@mui/material';

export default function ModalComponent({open, handleClose, skeleton, dataForModal}) {
    console.log(dataForModal);
    
    const render = React.useCallback((obj) => {

        const groups = []
        const clients = []

        for(let key in obj.groups) {
          groups.push(
            <Box className='compaigns_card' sx={{maxWidth: '360px', borderRight: 'solid 1px'}}>
                <Typography display={'flex'} justifyContent={'space-between'} className='card_text compaigns_text_card' variant='p'>
                  <span display={'flex'} justifyContent={'space-between'} className='titile_header'>Название: </span><span>{obj.groups[key].adGroupName}</span>
                </Typography>
                <Typography display={'flex'} justifyContent={'space-between'} className='card_text compaigns_text_card' variant='p'>
                  <span className='titile_header'>Количество кликов: </span><span>{obj.groups[key].clicks}</span>
                </Typography>
                <Typography display={'flex'} justifyContent={'space-between'} className='card_text compaigns_text_card' variant='p'>
                  <span className='titile_header'>Cумма затрат: </span><span>{obj.groups[key].cost.toFixed(2)}</span>
                </Typography>
            </Box>
          )
        }
        console.log(obj.clients);
        
        for(let key in obj.clients) {
          obj.clients[key].forEach(el => {
            el.forEach(client => {
              clients.push(
                <Box className='compaigns_card' sx={{maxWidth: '860px'}}>
                  <Typography display={'flex'} justifyContent={'space-between'} className='card_text' variant='p'>
                    <span className='titile_header'>Email клиента: </span><span>{client.client_mail}</span>
                  </Typography>
                  <Typography display={'flex'} justifyContent={'space-between'} className='card_text' variant='p'>
                    <span className='titile_header'>Сумма покупок: </span><span>{client.invoice_price}</span>
                  </Typography>
                  <Typography display={'flex'} justifyContent={'space-between'} className='card_text' variant='p'>
                    <span className='titile_header'>Дата покупки: </span><span>{client.invoice_date}</span>
                  </Typography>
                </Box>
              )
            })
          });
        }

        const html = (
          <>
                <Grid  item sx={{maxWidth: '500px', width: '100%'}} xs={4}>
                  {groups.map(el => el)}
                </Grid>
                <Grid sx={{maxWidth: '360px', width: '100%'}} item xs={6}>
                  {clients.map(el => el)}
                </Grid>
          </>
        )

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
           <Grid container spacing={2} sx={{width: '1200px', height: '760px'}}>
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
                {dataForModal ? render(dataForModal) : ''}
           </Grid>
        </DialogContent>
        <DialogActions>
          <Button onClick={handleClose}>Закрыть</Button>
        </DialogActions>
      </Dialog>
    </React.Fragment>
  );
}