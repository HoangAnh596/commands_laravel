PROJECT_DIR=$(shell pwd)
PROJ_TOP_DIR=$(PROJECT_DIR)/../php-ci
include $(PROJ_TOP_DIR)/config/Makefile.d/var.d/*.mk
include $(PROJ_TOP_DIR)/config/Makefile.d/register.d/app.mk
include $(PROJ_TOP_DIR)/config/Makefile.d/register.d/ci.mk
include $(PROJ_TOP_DIR)/config/Makefile.d/register.d/composer.mk
include $(PROJ_TOP_DIR)/config/Makefile.d/register.d/php.mk
include $(PROJ_TOP_DIR)/config/Makefile.d/task.d/*.mk
