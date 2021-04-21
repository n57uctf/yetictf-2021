<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  background: #555;
}

* {
  box-sizing: border-box;
}

input[type=text], select, textarea {
  width: 100%;
  padding: 12px;
  border: 1px solid #ccc;
  margin-top: 6px;
  margin-bottom: 16px;
  resize: vertical;
}

input[type=password], select, input {
  width: 50%;
  padding: 12px;
  border: 1px solid #ccc;
  margin-top: 1px;
  margin-bottom: 10px;
  resize: vertical;
}

input[type=text], select, input {
  width: 50%;
  padding: 12px;
  border: 1px solid #ccc;
  margin-top: 1px;
  margin-bottom: 10px;
  resize: vertical;
}



input[type=submit] {
  background-color:#787A77;
  color: white;
  padding: 12px 20px;
  border: none;
  cursor: pointer;
}

p.answer {
    text-align: center;
    border-radius: 0px;
    background-color:#787A77;
    padding: 10px;
    color: white;
}

p.down {
    text-align: center;
    border-radius: 0px;
    background-color:#787A77;
    padding: 10px;
    color: white;    
}

input[type=submit]:hover {
  background-color: #45a049;
}

.header {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 5px;
}

.label {
    border-radius: 0px;
    background-color:#787A77;
    padding: 10px;
    color: white;
}

.list {
    float: right;
    width: 20%;
    margin-top: 6px;
    padding: 20px;
}

.square {
  margin: 0;
  counter-reset: li;
  list-style: none;
  background:#E8E8E8;
  padding: 10px;
}
.square li {
  position: relative;
  margin: 0 0 10px 2em;
  padding: 4px 8px;
  border-top: 2px solid #787A77;
  transition: .3s linear;
}
.square li:last-child {margin-bottom: 0;}
.square li:before {
  content: counter(li);
  counter-increment: li;
  position: absolute;
  top: -2px;
  left: -2em;
  width: 2em;
  box-sizing: border-box;
  margin-right: 8px;
  padding: 4px;
  border-top: 2px solid #787A77;
  border-left: 2px solid transparent;
  border-right: 2px solid transparent;
  border-bottom: 2px solid transparent;
  background: #787A77;
  color: white;
  font-weight: bold;
  text-align: center;
  transition: .3s linear;
}

.area {
  float: left;
  width: 60%;
  margin-top: 6px;
  padding: 20px;
}
</style>
</head>
</html>