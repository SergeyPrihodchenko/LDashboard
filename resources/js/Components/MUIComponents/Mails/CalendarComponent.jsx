import * as React from 'react';
import { DemoContainer } from '@mui/x-date-pickers/internals/demo';
import { AdapterDayjs } from '@mui/x-date-pickers/AdapterDayjs';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { DatePicker } from '@mui/x-date-pickers/DatePicker';
import { ruRU } from '@mui/x-date-pickers/locales';

export default function CalendarComponent({lable, dateChange}) {

  return (
    <LocalizationProvider localeText={ruRU} dateAdapter={AdapterDayjs}>
      <DemoContainer components={['DatePicker']}>
        <DatePicker format='DD/MM/YYYY' label={lable} onChange={(e) => dateChange(e)} sx={{"& [type='text']:focus": {boxShadow: "none"}}}/>
      </DemoContainer>
    </LocalizationProvider>
  );
}