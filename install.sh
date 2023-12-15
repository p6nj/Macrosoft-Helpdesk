#!/bin/bash -e
cd src/
mariadb < destruction.sql
mariadb < creation.sql
mariadb < test_bd.sql
mariadb < test_bd_utilisateurs.sql
