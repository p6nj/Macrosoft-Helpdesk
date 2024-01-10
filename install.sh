#!/bin/bash -e
cd src/bd/
mariadb < creation.sql
mariadb < test_bd.sql
mariadb < test_bd_utilisateurs.sql
