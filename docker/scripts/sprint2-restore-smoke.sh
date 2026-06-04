#!/usr/bin/env bash
set -euo pipefail

# Smoke test de restore para Sprint 2.
# Fluxo:
# 1) cria base temporaria
# 2) executa restore completo
# 3) valida checks minimos
# 4) remove base (padrao)
#
# Uso:
#   ./docker/scripts/sprint2-restore-smoke.sh --file /tmp/ecidade-backups/active/ecidade_x.dump
#
# Opcoes:
#   --db-suffix <sufixo>    Sufixo da base temporaria (default: timestamp)
#   --keep-db               Nao remove a base ao final
#   --dry-run               Nao executa comandos, apenas exibe plano

BACKUP_FILE=""
DB_SUFFIX="$(date +%Y%m%d_%H%M%S)"
KEEP_DB="false"
DRY_RUN="false"

PGHOST="${RESTORE_PGHOST:-${DB_HOST:-localhost}}"
PGPORT="${RESTORE_PGPORT:-${DB_PORT:-5432}}"
PGUSER="${RESTORE_PGUSER:-${DB_USERNAME:-ecidade}}"
PGPASSWORD="${RESTORE_PGPASSWORD:-${DB_PASSWORD:-}}"
export PGPASSWORD

while [[ $# -gt 0 ]]; do
  case "$1" in
    --file)
      BACKUP_FILE="${2:-}"
      shift 2
      ;;
    --db-suffix)
      DB_SUFFIX="${2:-}"
      shift 2
      ;;
    --keep-db)
      KEEP_DB="true"
      shift
      ;;
    --dry-run)
      DRY_RUN="true"
      shift
      ;;
    *)
      echo "Parametro invalido: $1"
      echo "Uso: $0 --file <dump> [--db-suffix sufixo] [--keep-db] [--dry-run]"
      exit 1
      ;;
  esac
done

if [[ -z "${BACKUP_FILE}" ]]; then
  echo "Informe --file com caminho do dump."
  exit 1
fi

if [[ ! -f "${BACKUP_FILE}" ]]; then
  echo "Arquivo nao encontrado: ${BACKUP_FILE}"
  exit 1
fi

TARGET_DB="ecidade_restore_smoke_${DB_SUFFIX}"

psql_cmd() {
  psql -h "${PGHOST}" -p "${PGPORT}" -U "${PGUSER}" -d postgres -v ON_ERROR_STOP=1 -c "$1"
}

run_or_echo() {
  if [[ "${DRY_RUN}" == "true" ]]; then
    echo "DRY RUN> $*"
  else
    eval "$@"
  fi
}

echo "[S2-SMOKE] dump: ${BACKUP_FILE}"
echo "[S2-SMOKE] target_db: ${TARGET_DB}"
echo "[S2-SMOKE] conn: ${PGHOST}:${PGPORT} user=${PGUSER}"

run_or_echo "psql_cmd \"DROP DATABASE IF EXISTS ${TARGET_DB};\""
run_or_echo "psql_cmd \"CREATE DATABASE ${TARGET_DB};\""

RESTORE_CMD="pg_restore --host='${PGHOST}' --port='${PGPORT}' --username='${PGUSER}' --dbname='${TARGET_DB}' --clean --if-exists --no-owner --no-privileges --verbose '${BACKUP_FILE}'"
run_or_echo "${RESTORE_CMD}"

# Checks minimos de consistencia
CHECK_TABLES="select count(*) as total from information_schema.tables;"
CHECK_LEG="select to_regclass('public.leg_materias') as leg_materias;"

run_or_echo "psql -h '${PGHOST}' -p '${PGPORT}' -U '${PGUSER}' -d '${TARGET_DB}' -c \"${CHECK_TABLES}\""
run_or_echo "psql -h '${PGHOST}' -p '${PGPORT}' -U '${PGUSER}' -d '${TARGET_DB}' -c \"${CHECK_LEG}\""

if [[ "${KEEP_DB}" == "true" ]]; then
  echo "[S2-SMOKE] base mantida: ${TARGET_DB}"
else
  run_or_echo "psql_cmd \"DROP DATABASE IF EXISTS ${TARGET_DB};\""
  echo "[S2-SMOKE] base removida: ${TARGET_DB}"
fi

echo "[S2-SMOKE] concluido"
