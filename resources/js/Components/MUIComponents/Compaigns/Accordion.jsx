import * as React from 'react';
import Accordion from '@mui/material/Accordion';
import AccordionSummary from '@mui/material/AccordionSummary';
import AccordionDetails from '@mui/material/AccordionDetails';
import ExpandMoreIcon from '@mui/icons-material/ExpandMore';

const AccordionCompaign = ({title, cost, details}) => {

    const parserDetaile = React.useCallback((details) => {
        const data = []

        for(let key in details) {
            data.push(details[key].name+' :'+' '+details[key].cost+' руб.')
        }

        return data
    })

    const renderDetaile = React.useCallback((detail, key) => {

        return (
            <AccordionDetails key={key} className='accordion_detail'>
                {detail}
            </AccordionDetails>
        )
    })
    
    return (
        <Accordion className='accordion'>
        <AccordionSummary
            className='accordion_summary'
            expandIcon={<ExpandMoreIcon />}
            aria-controls="panel1-content"
            id="panel1-header"
        >
          {title} : {cost} руб.
        </AccordionSummary>
            {parserDetaile(details).map(el => renderDetaile(el))}
      </Accordion>
    )

}

export default AccordionCompaign