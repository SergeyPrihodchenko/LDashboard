import * as React from 'react';
import Box from '@mui/material/Box';
import { Button, ButtonGroup, Typography } from '@mui/material';
import { Link } from '@inertiajs/react';
import {PublishedWithChanges, Sync} from '@mui/icons-material';
import axios from 'axios';

const checkUpdate = (date) => {
  const dateUpdate = new Date(date)
  const dateTo = new Date()
  
  if(dateUpdate.toDateString() == dateTo.toDateString()) {
    return true
  } else {
    return false
  }
}

export default function Header({dateUpdateDirect, updateDirectDate}) {
  const [updated, setUpdated] = React.useState(checkUpdate(dateUpdateDirect));
  const [stateButton, setStateButton] = React.useState('success');
  const updateDirect = () => {
    setStateButton('warning')
    axios.post(route('update.direct'))
    .then(res => {      
      console.log(res.data);
      
      updateDirectDate(res.data.date)
      setUpdated(true)
    })
    .catch(err => {
      console.log(err);
    })
  }

  return (
    <Box sx={{ width: '100%', height: 100, display: 'flex', alignItems: 'center', justifyContent: 'space-between', borderBottom: 'solid 1px'}}>
        <ButtonGroup variant='contained'>
            <Link href={route('chart.wika')}><Button >Отчеты</Button></Link>
            <Link href={route('compaigns.wika')}><Button>Аналитика рекламы</Button></Link>
            <Link href={route('wika')}><Button>Список клиентов</Button></Link>
        </ButtonGroup>
        <Box sx={{display: 'flex', gap: 1}}>
          <Typography variant="h6" gutterBottom color={'white'} maxWidth={'290px'}>Дата последнего обновления: {dateUpdateDirect}</Typography>
          <Button disabled={updated} variant='contained' color={stateButton} onClick={updateDirect}>
          {
            updated ? <PublishedWithChanges sx={{'&, svg': {fontSize: '40px'}}}/> : <Sync sx={{'&, svg': {fontSize: '40px'}}}/>
          }
          </Button>
        </Box>
    </Box>
  );
}