#!/usr/bin/env bash
set -euo pipefail

# Sprint 2 - validacao operacional (backup/restore)
# Uso:
#   ./docker/scripts/sprint2-validate.sh
#   ./docker/scripts/sprint2-validate.sh --run-backup

RUN_BACKUP="false"
while [[ $# -gt 0 ]]; do
  case "$1" in
    --run-backup)
      RUN_BACKUP="true"
      shift
      ;;
    *)
      echo "Parametro invalido: $1"
      echo "Uso: $0 [--run-backup]"
      exit 1
      ;;
  esac
done

ROOT_DIR="$(cd "$(dirname "$0")/../.." && pwd)"
cd "${ROOT_DIR}"

echo "[S2-VAL] 1/4 Validando sintaxe dos scripts"
bash -n docker/scripts/backup-retention.sh
bash -n docker/scripts/restore-backup.sh

echo "[S2-VAL] 2/4 Verificando binarios necessarios"
command -v pg_dump >/dev/null || { echo "pg_dump nao encontrado"; exit 1; }
command -v pg_dumpall >/dev/null || { echo "pg_dumpall nao encontrado"; exit 1; }
command -v pg_restore >/dev/null || { echo "pg_restore nao encontrado"; exit 1; }
command -v sha256sum >/dev/null || { echo "sha256sum nao encontrado"; exit 1; }

echo "[S2-VAL] 3/4 Dry-run de backup"
BACKUP_DIR="${BACKUP_DIR:-/tmp/ecidade-backups}" ./docker/scripts/backup-retention.sh --dry-run

echo "[S2-VAL] 4/4 Resultado"
if [[ "${RUN_BACKUP}" == "true" ]]; then
  echo "Executando backup real..."
  ./docker/scripts/backup-retention.sh
  echo "Backup real concluido."
else
  echo "Validacao concluida (sem executar backup real)."
  echo "Para executar backup real: ./docker/scripts/sprint2-validate.sh --run-backup"
fi
