import { useEffect, useState } from 'react';

import { type Variant, variants } from '@/Components/Styles/Variants';

type Props = {
  text?: string | null;
  type?: Variant;
  duration?: number;
};

export default function FlashMessage({ text, type = 'default', duration = 3000 }: Props) {
  const [visible, setVisible] = useState(true);

  useEffect(() => {
    if (!text) {
      return;
    }

    const timer = setTimeout(() => {
      setVisible(false);
    }, duration);

    return () => clearTimeout(timer);
  }, [text, duration]);

  if (!text || !visible) {
    return null;
  }

  const styles = variants[type];

  return (
    <div className={['mb-4 rounded-lg p-4', styles.background, styles.text].join(' ')}>{text}</div>
  );
}
