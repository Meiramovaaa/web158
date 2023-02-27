const finalDiv = document.getElementById("comments")
const base_url = document.body.dataset.baseurl
const blog_id = document.body.dataset.blogid
const textarea = document.getElementById("textarea")
const addBtn = document.getElementById("add_btn")
const currentUser = document.body.dataset.authorid
const blogAuthor = document.body.dataset.blogauthor


function getData(){
    axios.get(`${base_url}/api/comment/list.php?id=${blog_id}`).then(res =>{
        console.log(res.data);
        showComments(res.data)
    })
}


getData()

function showComments(comments){
    let divHTML = `<h2> ${comments.length} комментария </h2>`
    for(let item of comments){
        let deleteBtn = '<span> </span>'
        if(currentUser == blogAuthor || currentUser == item.author_id){
            deleteBtn = `<span onclick="removeComment('${item.id}')">Delete</span>`
        }
        divHTML += `
            <div class="comment">
                <div class="comment-header">
                    <div class="comment-info">
                        <img src="images/avatar.png" alt="">
                        ${item.full_name}
                    </div>
                    ${deleteBtn}
                </div>
                <p>${item.text}</p>
            </div>
        `
    }
    finalDiv.innerHTML = divHTML
}

addBtn.onclick = function(){
    axios.post(`${base_url}/api/comment/add.php`, {
        text:textarea.value,
        author_id:currentUser,
        blog_id:blog_id
    }).then(res =>{
        getData()
        textarea.value = ""
    })
}

function removeComment(id){
    axios.delete(`${base_url}/api/comment/delete.php?id=${id}`).then(res =>{
        getData()
    })
}