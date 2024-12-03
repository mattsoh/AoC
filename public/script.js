document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    let year_group = document.getElementById('year_group').value;
    
    if (year_group==='') {
        document.getElementById('error-message').textContent = 'Year group is required!';
    } else {
        this.submit(); // If the fields are filled, submit the form
    }
});
