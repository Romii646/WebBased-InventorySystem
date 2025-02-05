function showNeededFields(table, accessories, monitors, motherboards, ramsticks, powersupplies){
    // declared variables
    const tableSelect = document.getElementById(table).value;
    const accessoryField = document.getElementById(accessories);
    const monitorsFields = document.getElementById(monitors);
    const motherboardsFields = document.getElementById(motherboards);
    const ramsticksFields = document.getElementById(ramsticks);
    const powerSupplyFields = document.getElementById(powersupplies);

    accessoryField.classList.add('hidden');
    monitorsFields.classList.add('hidden');
    motherboardsFields.classList.add('hidden');
    ramsticksFields.classList.add('hidden');
    powerSupplyFields.classList.add('hidden');

    if(tableSelect === 'accessories') {
        accessoryField.classList.remove('hidden');
    }
    else if (tableSelect === 'monitors') {
        monitorsFields.classList.remove('hidden');
    }
    else if (tableSelect === 'motherboards') {
        motherboardsFields.classList.remove('hidden');
    }
    else if (tableSelect === 'ramsticks') {
        ramsticksFields.classList.remove('hidden');
    }
    else if (tableSelect === 'powersupplies') {
        powerSupplyFields.classList.remove('hidden');
    }
}// end of addShowNeededFields

//function to clear form values
function clearForm(formId){
    formValue = document.getElementById(formId);
    formValue.reset();
    showNeededFields(); // reset for all hidden entries
}

// function to fetchTable pcSetUp which is for the main table that keeps track of computers currently on the lab floor
function fetchTable() {
    fetch('pcSetUpProcess.php?action=view')
        .then(response => {
            console.log('Response Status:', response.status); // Log the HTTP status
            return response.json();
        })
        .then(data => {
            if (data.message) {
                document.getElementById('pcSetUp-table').innerHTML = data.message;
            } 
            else {
                let table = '<table border="1"><tr>';
                for (let key in data[0]) {
                    table += `<th>${key}</th>`;
                }
                table += '</tr>';
                data.forEach(row => {
                    table += '<tr>';
                    for (let key in row) {
                        table += `<td>${row[key]}</td>`;
                    }
                    table += '</tr>';
                });
                table += '</table>';
                document.getElementById('pcSetUp-table').innerHTML = table;
            }
        })
        .catch(error => {
            console.error('Error in fetchTable:', error);
            document.getElementById('error').innerHTML = '<p>Error loading data.</p>';
        });
}

function viewTable() {
    document.addEventListener('DOMContentLoaded', fetchTable);
}

viewTable(); //loads method automatically when the page loads.

// function add is used to add new entries to the pcSetUP table and update to table
function addTable(){
    document.getElementById('Form').addEventListener("submit", function(event){
       event.preventDefault();
       var nameValue = event.submitter.name;
       const pc_id = document.getElementById('pc_id').value;
       const mobo_id = document.getElementById('mobo_id').value;
       const gpu_id = document.getElementById('gpu_id').value;
       const ram_id = document.getElementById('ram_id').value;
       const psu_id = document.getElementById('psu_id').value;
       const monitor_id = document.getElementById('monitor_id').value;
       const acc_id = document.getElementById('acc_id').value;
       const kb_id = document.getElementById('kb_id').value;
       const mouse_id = document.getElementById('mouse_id').value;
       const tableLoc = document.getElementById('table_location').value;
       const PCcondition = document.getElementById('PCcondition').value;

       const fieldValueArray = {
        'pc_id' : pc_id,
        'mobo_id' : mobo_id,
        'gpu_id' : gpu_id,
        'ram_id' : ram_id,
        'psu_id' : psu_id,
        'monitor_id' : monitor_id,
        'acc_id' :acc_id,
        'kb_id' : kb_id,
        'mouse_id' : mouse_id,
        'tableLocation' : tableLoc,
        'PCcondition' : PCcondition
       };

       if(nameValue === 'add'){
        fetch('pcSetUpProcess.php?action=add', {
            method : 'POST',
            headers : {'content-type' : 'application/json' },
            body: JSON.stringify(fieldValueArray)
        })
        .then(() =>{
            fetchTable();
            document.getElementById('...').innerHTML = "Add successful.";
         })
        .catch(error =>{
            console.error('Error: ', error);
            document.getElementById('...').innerHTML = "add operation failed."
           });
       }
       else if(nameValue === 'update'){
        fetch('pcSetUpProcess.php?action=update', {
            method : 'POST',
            headers : {'content-type' : 'application/json'},
            body : JSON.stringify(fieldValueArray) 
        })
        .then(() =>{
            fetchTable();
            document.getElementById('...').innerHTML = "Update successful.";
        })
        .catch(error => {
            console.error('Error: ', error);
            document.getElementById('...').innerHTML = "Update failed.";
        })
       }

       /* fetch('pcSetUPProcess.php?action=add', {
        method : 'POST',
        headers : {
            'content-type' : 'application/json'
        },
        body: JSON.stringify(fieldValueArray)
       })
       .then(() =>{
        fetchTable();
        document.getElementById('...').innerHTML = "Add successful.";
       })
       .catch(error =>{
        console.error('Error: ', error);
        document.getElementById('...').innerHTML = "add operation failed."
       }) */
    });
}

// function used to update table
/* function updateRow(){
    document.getElementById('...').addEventListener("submit", function(event){
        event.preventDefault();
        const pc_id = document.getElementById('pc_id').value;
        const mobo_id = document.getElementById('mobo_id').value;
        const gpu_id = document.getElementById('gpu_id').value;
        const ram_id = document.getElementById('ram_id').value;
        const psu_id = document.getElementById('psu_id').value;
        const monitor_id = document.getElementById('monitor_id').value;
        const acc_id = document.getElementById('acc_id').value;
        const kb_id = document.getElementById('kb_id').value;
        const mouse_id = document.getElementById('mouse_id').value;
        const PCcondition = document.getElementById('PCcpndition').value;

        const fieldValueArray = {
            'pc_id' : pc_id,
            'mobo_pc' : mobo_id,
            'gpu_id' : gpu_id,
            'ram_id' : ram_id,
            'psu_id' : psu_id,
            'monitor_id' : monitor_id,
            'acc_id' :acc_id,
            'kb_id' : kb_id,
            'mouse_id' : mouse_id,
            'PCcondition' : PCcondition
           };

        fetch('pcSetUPProcess.php?action=update', {
            method : 'POST',
            headers : {
                'content-type' : 'application/json'
            },
            body : JSON.stringify(fieldValueArray) 
        })
        .then(() =>{
            fetchTable();
            document.getElementById('...').innerHTML = "Update successful.";
        })
        .catch(error => {
            console.error('Error: ', error);
            document.getElementById('...').innerHTML = "Update failed."
        })
    })
} */

// function to delete row from the pc set up table
function deleteRow(){
    document.getElementById('deleteRow').addEventListener("submit", function(event){
        event.preventDefault();
        const pc_id = document.getElementById('delete_pc_id').value;

        fetch('pcSetUpProcess.php?action=delete', {
            method : 'POST',
            headers : {'content-type' : 'application/json'},
            body : JSON.stringify({pc_id : pc_id})
        })
        .then(() => {
            fetchTable();
            document.getElementById('...').innerHTML = "Row deleted successfully."
        })
        .catch(error => {
            console.error('Error: ', error);
            document.getElementById('...').innerHTML = "Row deleted failed."
        })
    });
}