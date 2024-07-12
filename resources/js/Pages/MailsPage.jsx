import ControlPanelComponent from "@/Components/Mails/ControlPanelComponent";
import TableComponent from "@/Components/MUIComponents/Mails/TableComponent"
import Guest from "@/Layouts/GuestLayout";
import { Container } from "@mui/material";

const MailsPage = ({data}) => {

    return (
        <Guest>
            <ControlPanelComponent/>
            <Container maxWidth={'1600px'}>
                <TableComponent data={data}/>
            </Container>
        </Guest>
    )
}

export default MailsPage