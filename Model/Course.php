<?php
    class Course{
        private $courseCode;
        private $title;
        private $weeklyHours;
        function __construct($courseCode, $title, $weeklyHours) {
            $this->courseCode = $courseCode;
            $this->title = $title;
            $this->weeklyHours = $weeklyHours;
        }
        function getCourseCode() {
            return $this->courseCode;
        }

        function getTitle() {
            return $this->title;
        }

        function getWeeklyHours() {
            return $this->weeklyHours;
        }

        function setCourseCode($courseCode): void {
            $this->courseCode = $courseCode;
        }

        function setTitle($title): void {
            $this->title = $title;
        }

        function setWeeklyHours($weeklyHours): void {
            $this->weeklyHours = $weeklyHours;
        }



    }