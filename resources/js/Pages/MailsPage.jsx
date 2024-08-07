import ControlPanelComponent from "@/Components/Mails/ControlPanelComponent";
import TableComponent from "@/Components/MUIComponents/Mails/TableComponent"
import Guest from "@/Layouts/GuestLayout";
import { Container } from "@mui/material";
import { useState } from "react";

const MailsPage = ({data}) => {

    console.log(data);
    const [dateUpdate, setDateUpdate] = useState(data.dateUpdateDirect)

    const updateDirectDate = (date) => {
        setDateUpdate(date)
    }

    return (
        <Guest dateUpdateDirect={dateUpdate} updateDirectDate={updateDirectDate}>
            <ControlPanelComponent title={data.title}/>
            <Container maxWidth={'1600px'}>
                <TableComponent data={data}/>
            </Container>
        </Guest>
    )
}

export default MailsPage