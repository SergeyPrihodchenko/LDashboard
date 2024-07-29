import * as React from 'react';
import Box from '@mui/material/Box';
import { Button, ButtonGroup } from '@mui/material';
import { Link } from '@inertiajs/react';


export default function Header() {
  const [value, setValue] = React.useState(0);

  return (
    <Box sx={{ width: '100%', height: 100, display: 'flex', alignItems: 'center', borderBottom: 'solid 1px'}}>
        <ButtonGroup variant='contained'>
            <Link href={route('chart.wika')}><Button >Отчеты</Button></Link>
            <Link href={route('compaigns.wika')}><Button>Аналитика рекламы</Button></Link>
            <Link href={route('wika')}><Button>Список клиентов</Button></Link>
        </ButtonGroup>
    </Box>
  );
}