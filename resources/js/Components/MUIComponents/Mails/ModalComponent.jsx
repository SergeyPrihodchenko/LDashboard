import * as React from 'react';
import Button from '@mui/material/Button';
import Dialog from '@mui/material/Dialog';
import DialogActions from '@mui/material/DialogActions';
import DialogContent from '@mui/material/DialogContent';
import { Container, Grid, Skeleton, Typography } from '@mui/material';

export default function ModalComponent({open, handleClose, dataModal, skeleton, data}) {

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
                        {
                            obj[key].map((elem, i) => {
                                let status = ''
                                switch (elem.invoice_status) {
                                    case 0:
                                        status = 'счет создан'
                                        break;
                                    case 1:
                                        status = 'счет выставлен'
                                        break;
                                    case 2:
                                        status = 'счет оплачен'
                                        break;
                                }
                                if(elem.title == '1С') {
                                    return (
                                        <Grid className='card' container key={i + elem.invoice_date}>
                                            <Grid item xs={12}>
                                            <Container sx={{display: 'flex', flexDirection: 'column'}}>
                                                <Typography variant='h5'>
                                                    {elem.title}
                                                </Typography>
                                                <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>Статус заказа: </span><span>{status}</span>
                                                </Typography>
                                                <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>Сумма заказа: </span><span>{elem.invoice_price}</span>
                                                </Typography>
                                                <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>Дата заказа: </span><span>{elem.invoice_date}</span>
                                                </Typography>
                                            </Container>
                                            </Grid>
                                        </Grid>

                                    )
                                } else {
                                    return ( 
                                    <Grid className='card' container key={i}>
                                        <Grid item xs={12}>
                                        <Container sx={{display: 'flex', flexDirection: 'column'}}>
                                            <Typography variant='h5'>
                                                {elem.title}
                                            </Typography>
                                            <Typography className='card_text' variant='p'>
                                               <span className='titile_header'>Дата просмотра: </span><span>{elem.date}</span>
                                            </Typography>
                                            <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>Просмотр: </span><span>{elem.url}</span>
                                            </Typography>
                                            <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>домен: </span><span>{elem.favicon}</span>
                                            </Typography>
                                            {
                                                elem.adGroupName ? 
                                                <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>Название рекламной группы: </span><span>{elem.adGroupName}</span>
                                                </Typography> :
                                                ''
                                            }
                                            {
                                                elem.adGroupName ? 
                                                <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>Название рекламной компании: </span><span>{elem.campaignName}</span>
                                                </Typography> :
                                                ''
                                            }
                                            {
                                                elem.adGroupName ? 
                                                <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>Ключевая фраза: </span><span>{elem.keyPhrase}</span>
                                                </Typography> :
                                                ''
                                            }
                                            {
                                                elem.adGroupName ? 
                                                <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>Город: </span><span>{elem.city}</span>
                                                </Typography> :
                                                ''
                                            }
                                            {
                                                elem.adGroupName ? 
                                                <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>Стоимость перехода по ссылке: </span><span>{elem.avgCpc}</span>
                                                </Typography> :
                                                ''
                                            }
                                            <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>посещения: </span><span>{elem.meric_visits}</span>
                                            </Typography>
                                            <Typography className='card_text' variant='p'>
                                                <span className='titile_header'>пользователи: </span><span>{elem.meric_users}</span>
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
           <Grid container spacing={2}>
                <Grid item xs={4} sx={{width: '800px', height: '650px'}}>
                    <Container sx={{width: 500, position: 'fixed', '&, css-1xgtf4e-MuiContainer-root': {padding: 0}}}>
                        <Container sx={{marginBottom: 1.2}}>
                            <p className='titile_header'>Название сайта: </p>
                            <p>{dataModal.headers.title}</p>
                        </Container>
                        <Container sx={{marginBottom: 1.2}}>
                            <p className='titile_header'>Код клиента: </p>
                            <p>{dataModal.headers.code}</p>
                        </Container>
                        <Container sx={{marginBottom: 1.2}}>
                            <p className='titile_header'>Электронная почта: </p>
                            <p>{dataModal.headers.mail}</p>
                        </Container>
                        <Container sx={{marginBottom: 1.2}}>
                            <p className='titile_header'>ID клиента: </p>
                            <p>{dataModal.headers.id}</p>
                        </Container>
                        <Container sx={{marginBottom: 1.2}}>
                            <p className='titile_header'>Яндекс ID клиента: </p>
                            <p>{dataModal.headers.ym_uid}</p>
                        </Container>
                        <Container sx={{marginBottom: 1.2}}>
                            <p className='titile_header'>Количество переходов по ссылке: </p>
                            <p>{dataModal.headers.countClicks}</p>
                        </Container>
                        <Container sx={{marginBottom: 1.2}}>
                            <p className='titile_header'>общая стоимость переходов по ссылке: </p>
                            <p>{dataModal.headers.costClicks}</p>
                        </Container>
                        <Container sx={{marginBottom: 1.2}}>
                            <p className='titile_header'>Сумма оплаты: </p>
                            <p>{dataModal.headers.sumPrice}</p>
                        </Container>
                    </Container>
                </Grid>
                <Grid item xs={8}>
                {
                    !skeleton ?
                    <>
                    <Grid container className='card_group'>
                        <Grid container className='card_group_items'>
                            <Skeleton variant="rectangular" width={960} height={260} />
                        </Grid>
                    </Grid>
                    <br />
                    <Grid container className='card_group'>
                        <Grid container className='card_group_items'>
                            <Skeleton variant="rectangular" width={960} height={260} />
                        </Grid>
                    </Grid>
                    </> : ''
                }
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