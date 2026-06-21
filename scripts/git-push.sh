#!/usr/bin/env sh
set -e
MSG="$1"
if [ -z "$MSG" ]; then
  echo "Usage: ./scripts/git-push.sh \"commit message\""
  exit 1
fi

echo "Staging all changes..."
git add -A

echo "Committing with message: $MSG"
git commit -m "$MSG"

echo "Pushing to remote..."
git push

echo "Done."
