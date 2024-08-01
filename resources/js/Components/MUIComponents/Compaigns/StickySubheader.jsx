import * as React from 'react';
import List from '@mui/material/List';
import ListItem from '@mui/material/ListItem';
import ListItemText from '@mui/material/ListItemText';
import ListSubheader from '@mui/material/ListSubheader';

const prepareData = (direct, clients) => {
    const data = {}
    data.campaigns = {}
    data.groups = {}

  for(let key in direct) {
      if(key in clients.clientsByCompaign) {
        let invoiceCost = 0
        clients.clientsByCompaign[key].forEach(el => {
          invoiceCost += +el.invoice_price
        });
        data.campaigns[key] = `Комания: ${direct[key].campaignName} -- Затраты: ${direct[key].cost} -- Прибыль: ${invoiceCost}`
      }

      for(let i in direct[key].AdGroupId) {
        if(i in clients.clientsByGroup && clients.clientsByGroup.length != 0) {
          let invoiceCost = 0
          clients.clientsByGroup[i].forEach(el => {
            invoiceCost += +el.invoice_price
          });
          if(key in data.groups) {
            data.groups[key].push(`${direct[key].AdGroupId[i].name} -- Затраты: ${direct[key].AdGroupId[i].cost} -- Прибыль: ${invoiceCost}`)
          } else {
            data.groups[key] = []
            data.groups[key].push(`${direct[key].AdGroupId[i].name} -- Затраты: ${direct[key].AdGroupId[i].cost} -- Прибыль: ${invoiceCost}`)
          }
         
        }
      }
  }

  return data
}

const render = (data) => {
  const domElems = [];

  for(let key in data.campaigns) {
    domElems.push((
      <li key={`section-${key}`}>
      <ul>
      <hr />
        <ListSubheader sx={{fontSize: '1.1em', color: 'black'}}>{`${data.campaigns[key]}`}</ListSubheader>
        {data.groups[key].map((item) => (
          <ListItem key={`item-${key}-${item}`}>
            <ListItemText sx={{color: 'gray'}} primary={`${item}`}/>
          </ListItem>
        ))}
      </ul>
    </li>
    ))
  }
  
  return domElems
}

export default function StickySubheader({direct, clients}) {

    console.log(direct, clients);

  return (
    <List
      sx={{
        width: '100%',
        maxWidth: 860,
        bgcolor: 'background.paper',
        position: 'relative',
        overflow: 'auto',
        maxHeight: 600,
        '& ul': { padding: 0 },
      }}
      subheader={<li />}
    >
      {clients.length != 0 ? render(prepareData(direct, clients)) : ''}
    </List>
  );
}