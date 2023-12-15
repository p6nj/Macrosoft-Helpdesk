#!/bin/bash -e
cd src/bd/
mariadb < destruction.sql
mariadb < creation.sql
mariadb < test_bd.sql
mariadb < test_bd_utilisateurs.sql
