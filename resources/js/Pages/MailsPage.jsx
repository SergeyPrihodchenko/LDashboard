import ControlPanelComponent from "@/Components/Mails/ControlPanelComponent";
import TableComponent from "@/Components/MUIComponents/Mails/TableComponent"
import Guest from "@/Layouts/GuestLayout";
import { Container } from "@mui/material";

const MailsPage = ({data}) => {
console.log(data);
    return (
        <Guest>
            <ControlPanelComponent title={data.title}/>
            <Container maxWidth={'1600px'}>
                <TableComponent data={data}/>
            </Container>
        </Guest>
    )
}

export default MailsPage