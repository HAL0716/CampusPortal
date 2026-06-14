import MenuCards from '@/Components/Menu/List';
import PageTitle from '@/Components/Typography/PageTitle';
import { menuItems } from '@/Constants/menus';

export default function Index() {
  return (
    <div className="space-y-6">
      <PageTitle title="管理者ホーム" />

      <MenuCards items={menuItems.admin} />
    </div>
  );
}
