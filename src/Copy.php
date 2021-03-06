<?php
    class Copy
    {
        private $id;
        private $book_id;
        private $due_date;
        private $status;

        function __construct($id = null, $book_id, $due_date, $status)
        {
            $this->id = $id;
            $this->book_id = $book_id;
            $this->due_date = $due_date;
            $this->status = $status;
        }

        function setId($new_id)
        {
            $this->id = $new_id;
        }

        function setDueDate($new_due_date)
        {
            $this->due_date = $new_due_date;
        }

        function setStatus($new_status)
        {
            $this->status = $new_status;
        }

        function getDueDate()
        {
            return $this->due_date;
        }

        function getStatus()
        {
            return $this->status;
        }

        function getBookId()
        {
            return $this->book_id;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO copies (book_id, due_date, status) VALUES ({$this->getBookId()}, '{$this->getDueDate()}', {$this->getStatus()});");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $copies = array();

            foreach($returned_copies as $copy)
            {
                $id = $copy['id'];
                $book_id = $copy['book_id'];
                $due_date = $copy['due_date'];
                $status = $copy['status'];
                $new_copy = new Copy($id, $book_id, $due_date, $status);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies");
        }

        static function find($search_id)
        {
            $found_copy = null;
            $returned_copies = Copy::getAll();

            foreach($returned_copies as $copy)
            {
                $copy_id = $copy->getId();
                if ($copy_id == $search_id)
                {
                    $found_copy = $copy;
                }
            }
            return $found_copy;
        }

        function update($new_due_date, $new_status)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET due_date = '{$new_due_date}', status = {$new_status} WHERE id = {$this->getId()};");
            $this->setDueDate($new_due_date);
            $this->setStatus($new_status);
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM copies WHERE id = {$this->getId()};");
        }

        function addPatron($patron)
        {
            $GLOBALS['DB']->exec("INSERT INTO copies_patrons (patron_id, copy_id) VALUES ({$patron->getId()}, {$this->getId()});");
        }

        function getPatrons()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT patrons.* FROM copies JOIN copies_patrons ON (copies.id = copies_patrons.copy_id) JOIN patrons ON (copies_patrons.patron_id = patrons.id) WHERE copies.id = {$this->getId()};");
            $patrons = array();

            foreach($returned_patrons as $patron)
            {
                $id = $patron['id'];
                $first_name = $patron['first_name'];
                $last_name = $patron['last_name'];
                $phone_number = $patron['phone_number'];
                $new_patron = new Patron($id, $first_name, $last_name, $phone_number);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

    }
?>
