function checkForm() {
    let inputs = document.querySelectorAll("input[required]");
    for (let input of inputs) {
        if (input.value.trim() === "") {
            alert("Please fill in all required fields.");
            input.focus();
            return false;
        }
    }
    return true;
}

function searchCourses() {
    let text = document.getElementById("search").value;

    fetch("ajax_search.php?q=" + encodeURIComponent(text))
        .then(response => response.text())
        .then(data => {
            document.getElementById("courseList").innerHTML = data;
        });
}
