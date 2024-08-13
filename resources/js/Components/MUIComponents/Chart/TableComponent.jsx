import * as React from 'react';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TableRow from '@mui/material/TableRow';
import Paper from '@mui/material/Paper';
import { Typography } from '@mui/material';


export default function BasicTable({castomMetric}) {
  return (
    <TableContainer component={Paper}>
      <Table sx={{ minWidth: 650, padding: '15px 0' }} aria-label="simple table">
        <TableHead>
          <TableRow sx={{background: 'rgba(1,87,155,.6)'}}>
            <TableCell align="left"></TableCell>
            <TableCell align="center"><span style={{fontSize: '18px'}}>Визиты</span></TableCell>
            <TableCell align="center"><span style={{fontSize: '18px'}}>Заявки</span></TableCell>
            <TableCell align="center"><span style={{fontSize: '18px'}}>CPL</span></TableCell>
            <TableCell align="center"><span style={{fontSize: '18px'}}>CPC</span></TableCell>
            <TableCell align="center"><span style={{fontSize: '18px'}}>Cумма оплаченных счетов</span></TableCell>
          </TableRow>
        </TableHead>
        <TableBody>
            <TableRow
              sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
            >
              <TableCell align="center"><Typography variant='h6'>Директ</Typography></TableCell>
              <TableCell align="center">{castomMetric.visits}</TableCell>
              <TableCell align="center">--</TableCell>
              <TableCell align="center">{castomMetric.cpl}</TableCell>
              <TableCell align="center">{castomMetric.cpc}</TableCell>
              <TableCell align="center">--</TableCell>
            </TableRow>
            <TableRow
              sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
            >
              <TableCell align="center"><Typography variant='h6'>Письма</Typography></TableCell>
              <TableCell align="center">--</TableCell>
              <TableCell align="center">{castomMetric.invoicesMail}</TableCell>
              <TableCell align="center">--</TableCell>
              <TableCell align="center">--</TableCell>
              <TableCell align="center">{castomMetric.mailPrice}</TableCell>
            </TableRow>
            <TableRow
              sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
            >
              <TableCell align="center"><Typography variant='h6'>Звонки</Typography></TableCell>
              <TableCell align="center">--</TableCell>
              <TableCell align="center">{castomMetric.invoicePhones}</TableCell>
              <TableCell align="center">--</TableCell>
              <TableCell align="center">--</TableCell>
              <TableCell align="center">{castomMetric.phonePrice}</TableCell>
            </TableRow>
        </TableBody>
      </Table>
    </TableContainer>
  );
}