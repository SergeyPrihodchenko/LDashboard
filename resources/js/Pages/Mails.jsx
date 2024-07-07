import ModalComponent from "@/Components/MUIComponents/Mails/ModalComponent";
import TableComponent from "@/Components/MUIComponents/Mails/TableComponent"

const Emails = ({data}) => {

    console.log(data);

    return (
        <>
        <div className="main_block">
            <div className="table_panel">

            </div>
            <div className="table_block">
                <TableComponent data={data}/>
            </div>
        </div>
        </>
    )
}

export default Emails