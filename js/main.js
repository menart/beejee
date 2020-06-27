const DATA_URL = 'app/view/getdata.php';

const DBQuery = class{
    getData = async (url) =>{
        const res = await fetch(url);
        if(res.ok){
            return res.json();
        } else {
            throw new Error(`По адресу ${url} сервер вернул ${res.status}`);
        }
    }

    getDataTable = (order = 'user',page = 0) => {
        return this.getData(`${DATA_URL}?order=${order}&$page=${page}`);
    }

}

const loadData = (order,page) => {
    new DBQuery().getDataTable(order,page).then(createTable);
}

const init = () => {
    loadData();
}

const createTable = data =>{
    const tableData = document.querySelector('.table_data');
    tableData.innerHTML = '';
    tableData.innerHTML = data.reduce((acc,item) =>{
       return `${acc}<div class="task col-lg-12" data-id="${item.id}">
                <div class="task_title row">
                    <div class="col-lg-4">Пользователь: <span class="data">${item.user}</span></div>
                    <div class="col-lg-4"> e-mail: <span class="data">${item.email}</span></div>
                    <div class="col-lg-2">${item.status == 0 ? 'Нев':'В'}ыполнено 
                        <span class="data hide">${item.status}</span> </div>
                </div>
                <div class="jumbotron">${item.context}</div>
            </div>`
    },'')
}