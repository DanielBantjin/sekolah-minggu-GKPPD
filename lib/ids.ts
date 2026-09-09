import { prisma } from "@/lib/prisma";

export async function getNextUserId() {
  const users = await prisma.user.findMany({ select: { id: true } });
  const usedIds = new Set(users.map((user) => user.id));
  let nextId = 1;
  while (usedIds.has(nextId)) nextId += 1;
  return nextId;
}
