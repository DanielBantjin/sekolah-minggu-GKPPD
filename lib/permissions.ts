const MANAGEMENT_ROLES = new Set(["admin", "sekretaris", "bendahara"]);

export function hasAdminAccess(roleName: string | null | undefined) {
  return MANAGEMENT_ROLES.has(roleName ?? "");
}
