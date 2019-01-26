#!/bin/bash
cd pages
grep -R "DB::" * | grep -v "tenant_id" | cut -d: -f1
cd ../templates
grep -R "DB::" * | grep -v "tenant_id" | cut -d: -f1
cd ..
