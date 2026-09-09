const MANAGEMENT_ROLES = new Set(["admin", "guru", "sekretaris", "bendahara"]);

export function isAdmin(roleName: string | null | undefined) {
  return roleName === "admin";
}

export function canManageWeeklyData(roleName: string | null | undefined) {
  return MANAGEMENT_ROLES.has(roleName ?? "");
}
