import Card from '@/Components/Menu/Card';
import { MenuItem } from '@/Constants/menus';

type Props = {
  items: MenuItem[];
};

export default function List({ items }: Props) {
  return (
    <div className="space-y-4">
      {items.map((item) => (
        <Card key={item.title} title={item.title} description={item.description} href={item.href} />
      ))}
    </div>
  );
}
