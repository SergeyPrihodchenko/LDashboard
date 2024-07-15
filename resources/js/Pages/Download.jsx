const Download = ({result}) => {
    console.log(result);
    return (
        <h1>{result ? 'yes' : 'no'}</h1>
    )
}

export default Download