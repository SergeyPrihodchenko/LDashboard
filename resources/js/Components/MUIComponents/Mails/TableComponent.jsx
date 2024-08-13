import * as React from 'react';
import Paper from '@mui/material/Paper';
import Table from '@mui/material/Table';
import TableBody from '@mui/material/TableBody';
import TableCell from '@mui/material/TableCell';
import TableContainer from '@mui/material/TableContainer';
import TableHead from '@mui/material/TableHead';
import TablePagination from '@mui/material/TablePagination';
import TableRow from '@mui/material/TableRow';
import ModalComponent from './ModalComponent';
import axios from 'axios';
import { Typography } from '@mui/material';

const columns = [
  { id: 'mail', label: 'Mail', minWidth: 170 },
  { id: 'created', label: 'счет создан', minWidth: 100 },
  {
    id: 'actived',
    label: 'выставлен клиенту на оплату',
    minWidth: 170,
    align: 'center'
  },
  {
    id: 'closed',
    label: 'пришла оплата',
    minWidth: 170,
    align: 'center',
  },
  {
    id: 'createdPrice',
    label: 'сумма созданных счетов',
    minWidth: 170,
    align: 'center',
    format: (value) => value.toFixed(2),
  },
  {
    id: 'activedPrice',
    label: 'выставленная сумма на оплату',
    minWidth: 170,
    align: 'center',
    format: (value) => value.toFixed(2),
  },
  {
    id: 'closedPrice',
    label: 'сума оплаченных счетов',
    minWidth: 170,
    align: 'center',
    format: (value) => value.toFixed(2),
  },
];

function maskEmailAddress(email) {
  const [username, domain] = email.split("@");
  
  const maskedDomain = domain.replace(/./g, "*");
  
  return `${username}@${maskedDomain}`;
}


const converter = (data) => {

  const newData = []
  const obj = {}
    
  data.forEach(el => {
    if(!obj.hasOwnProperty(el.client_mail)) {
      obj[el.client_mail] = {
        created: el.invoice_status == 0 ? 1 : 0,
        createdPrice: el.invoice_status == 0 ? Number(el.invoice_price) : 0.00,
        actived: el.invoice_status == 1 ? 1 : 0,
        activedPrice: el.invoice_status == 1 ? Number(el.invoice_price) : 0.00,
        closed: el.invoice_status == 2 ? 1 : 0,
        closedPrice: el.invoice_status == 2 ? Number(el.invoice_price) : 0.00
      }
    } else {
      obj[el.client_mail] = {
        created: el.invoice_status == 0 ? obj[el.client_mail].created+1 : obj[el.client_mail].created,
        createdPrice: el.invoice_status == 0 ? obj[el.client_mail].createdPrice + Number(el.invoice_price) : obj[el.client_mail].createdPrice,
        actived: el.invoice_status == 1 ? obj[el.client_mail].actived+1 : obj[el.client_mail].actived,
        activedPrice: el.invoice_status == 1 ? obj[el.client_mail].activedPrice + Number(el.invoice_price) : obj[el.client_mail].activedPrice,
        closed: el.invoice_status == 2 ? obj[el.client_mail].closed+1 : obj[el.client_mail].closed,
        closedPrice: el.invoice_status == 2 ? obj[el.client_mail].closedPrice + Number(el.invoice_price) : obj[el.client_mail].closedPrice
      }
    }
  });

  for(let key in obj) {
    newData.push({
      id: key,
      mail: maskEmailAddress(key),
      created: obj[key].created,
      actived: obj[key].actived,
      closed: obj[key].closed,
      createdPrice: obj[key].createdPrice,
      activedPrice: obj[key].activedPrice,
      closedPrice: obj[key].closedPrice
    })
  }

  return newData
}



export default function TableComponent({data}) {

    const [page, setPage] = React.useState(0);
    const [rowsPerPage, setRowsPerPage] = React.useState(10);
    const [rows, setRows] = React.useState(converter(data.rows))
    const [open, setOpen] = React.useState(false);
    const [dataModal, setDataModal] = React.useState({
      headers: {
        title: data.title,
        code: '',
        id: '',
        mail: '',
        ym_uid: '',
        countClicks: '',
        costClicks: '',
        sumPrice: ''
      },
      data: {}
    });
    const [skeleton, setSkeleton] = React.useState(false);

    const handleClose = () => {
      setOpen(false)
      setDataModal({
        headers: {
          title: data.title,
          code: '',
          id: '',
          mail: '',
          ym_uid: '',
          countClicks: '',
          costClicks: '',
          sumPrice: '',
        },
        data: {}
      })
    };

    const fetch = (mail) => {
      setOpen(true)
      setSkeleton(false)
      const data = new FormData
    
      data.set('mail', mail)
      let routePath = ''

      switch (dataModal.headers.title) {
        case 'wika':
          routePath = 'wika.general'
          break;
        case 'swagelo':
          routePath = 'swagelo.general'
          break;
        case 'hylok':
          routePath = 'hylok.general'
          break;
      }

      axios.post(route(routePath), data)
      .then(res => {
        setSkeleton(true)
        setDataModal({
          ...dataModal, 
          data: res.data.data,
          headers: {
            title: dataModal.headers.title,
            code: res.data.client_code,
            id: res.data.client_id,
            mail: maskEmailAddress(res.data.client_mail),
            ym_uid: res.data.client_ym_uid ?? 'отсутствует',
            countClicks: res.data.countClicks ?? 'отсутствует',
            costClicks: res.data.costClicks ?? 'отсутствует',
            sumPrice: res.data.sum_price,
          }
        })
      })
      .catch(err => {
        console.log(err);
      })
    
    }

    const handleChangePage = (event, newPage) => {
      setPage(newPage);
    };

    const handleChangeRowsPerPage = (event) => {
      setRowsPerPage(+event.target.value);
      setPage(0);
    };

    return (
        <Paper sx={{ width: '100%', margin: '0 auto'}}>
          <TableContainer sx={{ maxHeight: 600}}>
            <Table stickyHeader aria-label="sticky table">
              <TableHead>
                <TableRow>
                  <TableCell size='string' align="left" colSpan={12}>
                    <Typography variant='h3'>{data.title}</Typography>
                  </TableCell>
                </TableRow>
                <TableRow>
                  {columns.map((column) => (
                    <TableCell
                      key={column.id}
                      align={column.align}
                      style={{ top: 57, minWidth: column.minWidth }}
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
                      <TableRow hover role="checkbox" tabIndex={-1} key={key} sx={{cursor: 'pointer'}} onClick={() => fetch(row.id)}>
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
        <ModalComponent dataModal={dataModal} handleClose={handleClose} open={open} skeleton={skeleton}/>
    </Paper>
  );    
}