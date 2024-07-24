import * as React from 'react';
import List from '@mui/material/List';
import ListItem from '@mui/material/ListItem';
import ListItemText from '@mui/material/ListItemText';
import ListSubheader from '@mui/material/ListSubheader';

const prepareData = (direct, clients) => {
    const data = {}
    data.compaigns = []
    data.groups = []

    for(let key in clients.clientsByCompaign) {
        let invoiceClient = 0.00;
        for(let i in clients.clientsByCompaign[key]) {
            invoiceClient += +clients.clientsByCompaign[key][i].invoice_price
        }
        if(key in direct) {
            data.compaigns.push(`${direct[key].campaignName}: затраты: ${direct[key].cost} руб. | доход: ${invoiceClient} руб.`)
        }    
    }

    for(let key in clients.clientsByGroup) {

    }

    return data
}

export default function StickySubheader({direct, clients}) {
    console.log(direct, clients);

    const [comaigns, setCompaigns] = React.useState([])

    React.useEffect(() => {
        setCompaigns(prepareData(direct, clients).compaigns)
    }, [clients])
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
      {comaigns.map((sectionId) => (
        <li key={`section-${sectionId}`}>
          <ul>
            <ListSubheader>{`${sectionId}`}</ListSubheader>
            {[0, 1, 2].map((item) => (
              <ListItem key={`item-${sectionId}-${item}`}>
                <ListItemText primary={`Item ${item}`}/>
              </ListItem>
            ))}
          </ul>
        </li>
      ))}
    </List>
  );
}