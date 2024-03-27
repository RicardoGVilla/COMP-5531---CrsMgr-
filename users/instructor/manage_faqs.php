<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course FAQs</title>
    <style>
        /* Styles for modal and table */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Course FAQs</h2>
    <table>
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Current FAQs</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Database Systems</td>
                <td>
                    <ul>
                        <li>What is a relational database?</li>
                        <li>What is SQL?</li>
                        <li>What are the advantages of using indexes in databases?</li>
                    </ul>
                </td>
                <td><button onclick="openModal('Database Systems')">Add FAQs</button></td>
            </tr>
            <tr>
                <td>Algorithms and Data Structures</td>
                <td>
                    <ul>
                        <li>What is a linked list?</li>
                        <li>What is a binary search tree?</li>
                        <li>What is the time complexity of quicksort?</li>
                    </ul>
                </td>
                <td><button onclick="openModal('Algorithms and Data Structures')">Add FAQs</button></td>
            </tr>
        </tbody>
    </table>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Add FAQs</h3>
            <form id="faqForm">
                <label for="question">Question:</label><br>
                <input type="text" id="question" name="question" required><br><br>
                <label for="answer">Answer:</label><br>
                <textarea id="answer" name="answer" rows="4" required></textarea><br><br>
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>

    <script>
    </script>
</body>
</html>
