<?php
    class Semester{
        private $semesterCode;
        private $term;
        private $year;
        function __construct($semesterCode, $term, $year) {
            $this->semesterCode = $semesterCode;
            $this->term = $term;
            $this->year = $year;
        }
        function getSemesterCode() {
            return $this->semesterCode;
        }

        function getTerm() {
            return $this->term;
        }

        function getYear() {
            return $this->year;
        }

        function setSemesterCode($semesterCode): void {
            $this->semesterCode = $semesterCode;
        }

        function setTerm($term): void {
            $this->term = $term;
        }

        function setYear($year): void {
            $this->year = $year;
        }



    }
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

