import * as React from 'react';
import Paper from '@mui/material/Paper';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TablePagination from '@mui/material/TablePagination';
import TableRow from '@mui/material/TableRow';
import axios from 'axios';
import { Typography } from '@mui/material';
import ModalComponent from './ModalComponent';

const columns = [
  { id: 'title', label: 'Название компании', minWidth: 170 },
  {
    id: 'clients',
    label: 'количество клиентов по 1С',
    minWidth: 170,
    align: 'center',
    format: (value) => value,
  },
  {
    id: 'clicks',
    label: 'общее количество кликов',
    minWidth: 170,
    align: 'center',
    format: (value) => value,
  },
  {
    id: 'cost',
    label: 'сумма затрат на компанию',
    minWidth: 170,
    align: 'center',
    format: (value) => value.toFixed(2),
  },
  {
    id: 'profit',
    label: 'прибыль по клиентам из 1С',
    minWidth: 170,
    align: 'center',
    format: (value) => value.toFixed(2),
  }
];

const counterClients = (obj) => {
  let count = 0
  for(let key in obj) {    
    count += obj[key].length
  }

  return count
}

const countProfit = (obj) => {
  let count = 0
  
  for(let key in obj) {    
    obj[key].forEach(el => {
      el.forEach(client => {
        count += Number(client.invoice_price)
      })
    });
  }

  return count
}

const preparation = (compaignData) => {
    
  let rows = []
  
  for(let key in compaignData) {
      
      rows.push({
      title: compaignData[key].compaignName,
      clients: counterClients(compaignData[key].clients),
      clicks: compaignData[key].clicks,
      cost: compaignData[key].cost.toFixed(2),
      profit: countProfit(compaignData[key].clients),
      id: key
      })

  }

  return rows
}

const handleChangePage = (event, newPage) => {
  setPage(newPage);
};

const handleChangeRowsPerPage = (event) => {
  setRowsPerPage(+event.target.value);
  setPage(0);
};


export default function TableComponent({compaignsData}) {
  
    const [page, setPage] = React.useState(0);
    const [rowsPerPage, setRowsPerPage] = React.useState(10);
    const [open, setOpen] = React.useState(false);
    const [skeleton, setSkeleton] = React.useState(false);
    const [rows, setRows] = React.useState([]);
    const [dataForModal, setDataForModal] = React.useState([]);
  
    const openModal = (id) => {
      setOpen(true)
      setDataForModal(compaignsData[id])
      setSkeleton(true)
    }

    const handleClose = () => {
      setOpen(false)
    };

    React.useEffect(() => {
      setRows(preparation(compaignsData))
    }, [compaignsData])
    
    return (
        <Paper sx={{ width: '100%', margin: '0 auto'}}>
          <TableContainer sx={{ maxHeight: 600}}>
            <Table stickyHeader aria-label="sticky table">
              <TableHead>
                <TableRow>
                  <TableCell size='string' align="left" colSpan={12}>
                    <Typography variant='h3'>{}</Typography>
                  </TableCell>
                </TableRow>
                <TableRow>
                  {columns.map((column) => (
                    <TableCell
                      key={column.id}
                      align={column.align}
                      style={{ minWidth: column.minWidth }}
                    >
                      {column.label}
                    </TableCell>
                  ))}
                </TableRow>
              </TableHead>
              <TableBody>
                {rows
                  .slice(page * rowsPerPage, page * rowsPerPage + rowsPerPage)
                  .map((row, key) => {
                    return (
                      <TableRow hover role="checkbox" tabIndex={-1} key={key} sx={{cursor: 'pointer'}} onClick={() => openModal(row.id)}>
                        {columns.map((column) => {
                          const value = row[column.id];
                          return (
                            <TableCell key={column.id} align={column.align}>
                              {column.format && typeof value === 'number'
                                ? column.format(value)
                                : value}
                            </TableCell>
                          );
                        })}
                      </TableRow>
                    );
                  })}
              </TableBody>
            </Table>
          </TableContainer>
        <TablePagination
            rowsPerPageOptions={[10, 25, 100]}
            component="div"
            count={rows.length}
            rowsPerPage={rowsPerPage}
            page={page}
            onPageChange={handleChangePage}
            onRowsPerPageChange={handleChangeRowsPerPage}
            labelRowsPerPage={'Выводить по :'}
        />
        <ModalComponent handleClose={handleClose} open={open} skeleton={skeleton} dataForModal={dataForModal}/>
    </Paper>
  );    
}