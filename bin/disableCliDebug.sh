#!/bin/bash
[[ "${BASH_SOURCE[0]}" != "${0}" ]] || (>&2 echo "[FAIL] Please source script ... " )

export XDEBUG_CONFIG="remote_enable=0"
export PHP_IDE_CONFIG=""
